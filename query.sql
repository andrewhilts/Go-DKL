select r2.child as Design, r2.type as `type`, relationships.child as Action, relationships.type as `type1`, relationships.parent as `soft goal`, relationships.claim_id, class_type, claim_type FROM relationships inner join claim_classes ON relationships.claim_id = claim_classes.claim_id INNER JOIN relationships as r2 ON relationships.child = r2.parent INNER JOIN claims ON claims.claim_id = relationships.claim_id WHERE claim_classes.class_type = 'community building' AND (relationships.type = 'helps' OR relationships.type = 'hurts') ORDER BY `soft goal`;


